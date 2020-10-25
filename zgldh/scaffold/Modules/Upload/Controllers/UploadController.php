<?php namespace Modules\Upload\Controllers;

use App\Scaffold\AppBaseController;
use Modules\Upload\Requests\CreateUploadRequest;
use Modules\Upload\Requests\UpdateUploadRequest;
use Modules\Upload\Repositories\UploadRepository;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\ShowRequest;
use Illuminate\Http\JsonResponse;

class UploadController extends AppBaseController
{

    public function __construct(UploadRepository $uploadRepo)
    {
        $this->repository = $uploadRepo;
        $this->middleware('auth:api');
        $this->middleware("permission.auto")->except('store');
    }

    /**
     * Display a listing of the Upload.
     *
     * @param IndexRequest $request
     * @return JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Exception
     */
    public function index(IndexRequest $request)
    {
        $data = $this->repository->datatables(null, $request->getWith())
            ->search($request->getColumns(), null)
            ->result($request->getExportFileName());

        return $data;
    }

    /**
     * Store a newly created Upload in storage.
     *
     * @param CreateUploadRequest $request
     * @return JsonResponse
     */
    public function store(CreateUploadRequest $request)
    {
        $input = $request->all();

        $upload = $this->repository->create($input, null, $request->user());
        $upload->load($request->getWith());

        return $this->sendResponse($upload, 'Upload saved successfully.');
    }

    /**
     * Display the specified Upload.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function show($id, ShowRequest $request)
    {
        $upload = $this->repository->find($id);
        $upload->load($request->getWith());

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }

        return $this->sendResponse($upload, '');
    }

    /**
     * Update the specified Upload in storage.
     *
     * @param  int $id
     * @param UpdateUploadRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, UpdateUploadRequest $request)
    {
        $upload = $this->repository->find($id);

        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }
        $all = $request->all();

        $upload = $this->repository->update($all, $id);
        $upload->load($request->getWith());

        return $this->sendResponse($upload, 'Upload updated successfully.');
    }

    /**
     * Remove the specified Upload from storage.
     *
     * @param  int $id
     *
     * @return JsonResponse
     */
    public function destroy($id, IndexRequest $request)
    {
        $upload = $this->repository->find($id);
        if (empty($upload)) {
            return $this->sendError('Upload not found');
        }
        $this->repository->delete($id);

        return $this->sendResponse($upload, 'Upload deleted successfully.');
    }
}
