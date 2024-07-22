<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\ProcurementProjectManagementPlan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentsController extends Controller
{
    public function index($type, $id)
    {
        $document = ProcurementProjectManagementPlan::find($id);
        if ($type == 'pr')
            $document = PurchaseRequest::find($id);

        return response()->json(['attachments' => json_decode($document->attachments)]);
    }

    public function download($type, $id, $file)
    {
        $filePath = $type . '/' . $id . '/' . $file;

        if (Storage::disk('hfiledepot')->exists($filePath)){
            $originalFilename = basename(Storage::disk('hfiledepot')->path($filePath));
            $contentType = Storage::disk('hfiledepot')->mimeType($filePath);

            $response = new BinaryFileResponse(Storage::disk('hfiledepot')->path($filePath));
            // $response->setContentDisposition('attachment', $originalFilename, 'UTF-8');
            $response->headers->set('Content-Type', $contentType);
            return $response;
        }
        else
            abort(404, 'File not found.');
    }

    public function store(Request $request, $type, $id)
    {
        $storagePath = 'ppmp_attachments/'.$id;
        $document = ProcurementProjectManagementPlan::find($id);
        if ($type == 'pr'){
            $storagePath = 'pr_attachments/'.$id;
            $document = PurchaseRequest::find($id);
        }
        if ($request->hasFile('attachments')){
            $files = $request->file('attachments');
            $uploadedFiles = json_decode($document->attachments) ?? [];

            foreach ($files as $file) {
                $fileName = uniqid() . '~-~' . $file->getClientOriginalName();

                $path = Storage::disk('hfiledepot')->putFileAs($storagePath, $file, $fileName);

                $uploadedFiles[] = $path;
            }

            $document->attachments = $uploadedFiles;
            $result = $document->save();

            if ($result)
                return response()->json([
                    'message' => 'Files uploaded successfully'
                ]);

            return response()->json([
                'message' => 'File not saved to database.'
            ], 400);
        }
        
        return response()->json([
            'message' => 'No files were uploaded.'
        ], 400);
    }

    public function destroy($type, $id, $file)
    {
        $filePath = $type . '/' . $id . '/' . $file;

        if (Storage::disk('hfiledepot')->exists($filePath)){
            $result = Storage::disk('hfiledepot')->delete($filePath);

            $document = ProcurementProjectManagementPlan::find($id);
            if ($type == 'pr_attachments')
                $document = PurchaseRequest::find($id);
            
            $uploadedFiles = json_decode($document->attachments) ?? [];

            $index = array_search($filePath, $uploadedFiles);

            if ($index !== false) {
                array_splice($uploadedFiles, $index, 1);
                $document->attachments = json_encode($uploadedFiles);
            }
            $document->save();

            if ($result)
                return response()->json([
                    'message' => 'File deleted successfully.'
                ], 200);

            return response()->json([
                'message' => 'File not deleted from database.'
            ], 400);
        }
        else
            abort(404, 'File not found.');
    }
}
