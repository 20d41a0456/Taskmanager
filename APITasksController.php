<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskManager;
use App\Jobs\SendEmailJob;

class APITasksController extends Controller
{
    public function create(Request $request){
        $data = new TaskManager();
        $data->task_description = $request->get('task_description');
        $data->task_owner = $request->get('task_owner');
        $data->task_owner_email = $request->get('task_owner_email');
        $data->task_eta = $request->get('task_eta');
        if($data->save()){
            dispatch(new SendEmailJob($data));
            return "Task Saved Successfully";
        }else{
            return "Something Went Wrong";
        }
    }

    public function index(){
        
        $data = TaskManager::get();
        return $data;
    }

    public function getTaskById($id){
        $data = TaskManager::find($id);
        return $data;
    }

    public function update(Request $request,$id){
        $data = TaskManager::find($id);
        $data->task_description = $request->get('task_description');
        $data->task_owner = $request->get('task_owner');
        $data->task_owner_email = $request->get('task_owner_email');
        $data->task_eta = $request->get('task_eta');
        if($data->save()){
            return "Task Updated Successfully";
        }else{
            return "Something Went Wrong";
        }
    }

    public function markAsDone($id){
        $data = TaskManager::find($id);
        $data->status = 1;
        if($data->save()){
            dispatch(new SendEmailJob($data));
            return "Task Marked as Done Successfully";
        }else{
            return "Something Went Wrong";
        }
    }

    public function delete($id){
        $data = TaskManager::find($id);
        if($data->delete()){
            return "Task Deleted Successfully";
        }else{
            return "Something Went Wrong";
        }
    }
}
