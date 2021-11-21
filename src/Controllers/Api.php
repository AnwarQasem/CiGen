<?php

namespace Muravian\CiGen\Controllers;

use CodeIgniter\CodeIgniter;
use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Pager;
use App\Models;
use Psr\Log\LoggerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Api class to work with the CRUD React DataTable
 *
 * @author Anwar Subhi
 */
class Api extends BaseController
{

    use ResponseTrait;

    /**
     * Variable holding the current model
     *
     * @var
     */
    protected $model;

    /**
     * Variable holding the current table
     *
     * @var
     */
    protected $table;

    /**
     * @var string[]
     */
    protected $validationRules = [
        'email'        => 'valid_email|is_unique[users.email]',
        'password'     => 'min_length[8]',
    ];

    /**
     * @var \string[][]
     */
    protected $validationMessages = [
        'email' => [
            'valid_email' => 'email not valid',
            'is_unique' => 'email not unique'
        ]
    ];

    /**
     * Extending BaseController
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param LoggerInterface $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

    }

    /**
     * Index & Search items
     *
     * @param string|null $table
     * @return object
     */
    public function index(string $table = null)
    {
        $this->setDefauts($table);

        $search = json_decode($this->request->getVar('search'));
        $this->model->like((array)$search);

        $sort = json_decode($this->request->getVar('sort'));
        if (is_object($sort) && isset($sort->name)) {
            $this->model->orderBy($sort->name, $sort->value);
        }

        $valuePerPage = null;
        $perPage      = $this->request->getVar('perPage');
        if (isset($perPage) && strlen($perPage) > 0) {
            $valuePerPage = (int)$perPage;
        }

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'fields'  => $this->model->getFieldData(),
                'list'    => $this->model->paginate($valuePerPage),
                'pager'   => $this->model->pager->getDetails(),
                'perPage' => ['20', '50', '100']
            ]
        ], 200);
    }

    /**
     * Show single item
     *
     * @param string $table
     * @param int $id
     * @return object
     */
    public function show(string $table, int $id)
    {
        $this->setDefauts($table);

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'fields' => $this->model->getFieldData(),
                'show'   => $this->model->where('id', $id)->first(),
            ]
        ], 200);
    }

    /**
     * Create item
     *
     * @param string $table
     * @return object
     */
    public function create(string $table)
    {
        $this->setDefauts($table);

        $jsonReceived = $this->request->getJSON();

//        $this->model->setValidationRules($this->validationRules);
//        $this->model->setValidationMessages($this->validationMessages);

        $result = $this->model->insert($jsonReceived);

        if($result) {
            return $this->respond([
                'result' => 'success',
                'data'   => [
                    'inserted_id' => $result,
                ]
            ], 200);
        } else{
            return $this->respond([
                'result' => 'error',
                'data'   => [
                    'messages' => $this->model->validation->getErrors()
                ]
            ], 200);
        }
    }

    /**
     * Update item
     *
     * @param string $table
     * @param int $id
     * @return object
     */
    public function update(string $table, int $id)
    {
        $this->setDefauts($table);

        $jsonReceived = $this->request->getJSON();

        $result = $this->model->set((array)$jsonReceived)->where('id', $id)->update();

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'updated' => $result
            ]
        ], 200);
    }

    /**
     * Delete item
     *
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function delete(string $table, int $id)
    {
        $this->setDefauts($table);

        $result = $this->model->delete($id);

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'deleted' => $result
            ]
        ], 200);
    }

    /**
     * Delete Bulk
     *
     * @param string $table
     * @return mixed
     */
    public function deleteBulk(string $table)
    {
        $this->setDefauts($table);

        $jsonReceived = $this->request->getJSON();

        $result = $this->model->delete($jsonReceived);

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'deleted' => $result
            ]
        ], 200);
    }

    /**
     * Get fields for table
     *
     * @param string $table
     * @return object
     */
    public function fields(string $table)
    {

        $this->setDefauts($table);

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'fields' => $this->model->getFieldData()
            ]
        ], 200);
    }

    /**
     * Upload File
     *
     * @param $table
     * @param $id
     * @param $field
     * @return mixed
     */
    public function file_upload($table, $id, $field)
    {
        $this->setDefauts($table);
        $file = $this->request->getFile('files')->store('auth_profiles/');

        $this->model->set($field, $file)->where('id', $id)->update();

        return $this->respond([
            'result' => 'success',
            'data'   => $file
        ], 200);
    }

    /**
     * Delete File
     *
     * @param $table
     * @param $id
     * @param $field
     * @return mixed
     */
    public function file_delete($table, $id, $field)
    {
        $this->setDefauts($table);
        $row = $this->model->where('id', $id)->first();

        $file_path = $row->$field;
        unlink($file_path);

        $this->model->set($field, null)->update();

        return $this->respond([
            'result' => 'success',
            'data'   => $file_path
        ], 200);
    }

    /**
     * Get File
     *
     * @param $path
     * @param $file
     */
    public function file_get($path, $file)
    {

        $file_path = WRITEPATH . 'uploads/' . $path . "/" . $file;
        $image     = file_get_contents($file_path);

        if (!$image) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        $mimeType = mime_content_type($file_path);

        $this->response
            ->setStatusCode(200)
            ->setContentType($mimeType)
            ->setBody($image)
            ->send();
    }

    /**
     * Export File
     *
     * @param $table
     * @param $type
     */
    public function file_export($table, $type)
    {
        $this->setDefauts($table);

        $search = json_decode($this->request->getVar('search'));
        if (is_object($search)) {
            $this->model->like((array)$search);
        }

        $sort = json_decode($this->request->getVar('sort'));
        if (is_object($sort) && isset($sort->name)) {
            $this->model->orderBy($sort->name, $sort->value);
        }

        switch ($type) {
            case 'csv':
                $obj = $this->model->find();
                foreach ($obj as $value) {
                    $array[] = (array)$value;
                }
                $this->export_csv($table, $array);
                break;
        }

    }

    /**
     * Export CSV
     *
     * @param $table
     * @param $data
     * @return \CodeIgniter\HTTP\DownloadResponse|null
     */
    private function export_csv($table, $data)
    {
        $csv = $this->array2csv($data);
        return $this->response
            ->setHeader('Content-Disposition', 'attachment; filename=' . $table . "-" . date("Y-m-d H:i:s") . ".csv")
            ->setContentType('text/csv')
            ->noCache()
            ->download();
    }

    /**
     * Convert array to csv
     *
     * @param array $array
     * @return false|resource|null
     */
    function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return $df;
    }

    /**
     * Get import map
     *
     * @return mixed
     */
    public function get_import_map()
    {
        $this->setDefauts('import_map');
        $jsonReceived = $this->request->getJSON();

        $data = $this->model->select('data')->where('json', $jsonReceived)->first();

        return $this->respond([
            'result' => 'success',
            'data'   => $data
        ], 200);
    }

    /**
     * Save import map
     *
     * @param $table
     * @return mixed
     */
    public function put_import_map($table)
    {
        $this->setDefauts($table);
        $jsonReceived = $this->request->getJSON();
        $data         = [
            'table' => $table,
            'json'  => $jsonReceived->json,
            'data'  => $jsonReceived->data
        ];

        $inserted_id = $this->model->insert($data);

        return $this->respond([
            'result' => 'success',
            'data'   => [
                'inserted_id' => $data
            ]
        ], 200);
    }

    /**
     * Save import
     *
     * @param $table
     * @param $map_id
     * @return mixed
     */
    public function put_import($table, $map_id)
    {
        $data   = $this->getImportData();
        $filter = $this->getFilters($map_id);

        $newData = [];
        foreach ($data as $key => $val) {
            foreach ($val as $k => $v) {
                $newKey = $this->returnKey($filter, $k);
                $newData[$key][$newKey] = $v;
            }
        }

        $this->setDefauts($table);
        return $this->respond([
            'result' => 'success',
            'data'   => [
                'inserted_id' => $this->model->insertBatch($newData)
            ]
        ], 200);
    }

    /**
     * Get filters
     *
     * @param $map_id
     * @return mixed
     */
    private function getFilters($map_id)
    {
        $this->setDefauts('import_map');
        $filters = $this->model->select('data')->where('id', $map_id)->first();
        $filters = json_decode($filters->data);

        return $filters;
    }

    /**
     * Return key of object
     *
     * @param $obj
     * @param $search
     * @return false
     */
    private function returnKey($obj, $search)
    {
        $result = false;

        foreach ($obj as $value) {
            if (is_object($value->value) && $value->value->value === $search) {
                $result = $value->index;
            }
        }

        return $result;
    }

    /**
     * Get imported data
     *
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function getImportData()
    {
        $data         = [];
        $uploadedFile = $this->request->getFile('files')->store('imported_files/');
        $file_path    = ROOTPATH . "writable/uploads/" . $uploadedFile;

        $file = new File($file_path);

        $mime = $file->getMimeType();
        $ext  = $file->guessExtension();

        if ($mime === 'application/vnd.ms-excel' && $ext === 'csv') {
            $reader      = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $spreadsheet = $reader->load($file_path);

            $array = $spreadsheet->getSheet(0)->toArray();
        }

        if ($mime === 'text/plain' && $ext === 'csv') {
            $array = array_map('str_getcsv', file($file_path));
        }

        $headers = $array[0];
        unset($array[0]);

        $data = $this->arrangeArray($array, $headers);

        return $data;
    }

    /**
     * Arrange array for bulk import
     *
     * @param $array
     * @param $headers
     * @return array
     */
    private function arrangeArray($array, $headers)
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            foreach ($value as $k => $v) {
                $newArray[$key][$headers[$k]] = $v;
            }
        }
        return array_values($newArray);
    }

    /**
     * Simple page answering to /
     *
     * @return mixed
     */
    public function defaultAnswer()
    {
        return $this->respond([
            'result'  => 'success',
            'message' => ["API Working ... "]
        ], 200);
    }

    /**
     * Setting up Table & Model
     *
     * @param $table
     */
    private function setDefauts($table)
    {
        $this->model = model(ucfirst($table) . "Model");
        $this->table = $table;
    }

}