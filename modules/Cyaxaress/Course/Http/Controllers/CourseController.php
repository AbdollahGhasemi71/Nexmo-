<?php

namespace Cyaxaress\Course\Http\Controllers;

use Cyaxaress\Category\Repositories\CategoryRepo;
use Cyaxaress\Category\Responses\AjaxResponses;
use Cyaxaress\Course\Http\Requests\CourseRequest;
use Cyaxaress\Course\Models\Course;
use Cyaxaress\Course\Repositories\CourseRepo;
use Cyaxaress\Media\Services\MediaFileService;
use Cyaxaress\User\Repositories\UserRepo;
use Illuminate\Http\Request;


class CourseController
{
    public function index(CourseRepo $courseRepo)
    {
        $courses = $courseRepo->paginate();
        return view('Courses::index', compact('courses'));
    }

    public function create(UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request, CourseRepo $courseRepo)
    {
        $request->request->add(['banner_id' => MediaFileService::upload($request->file('image'))->id]);
        $courseRepo->store($request);
        return redirect(route('courses.index'));
    }

    public function edit($id, CourseRepo $courseRepo, UserRepo $userRepo, CategoryRepo $categoryRepo)
    {
        $course = $courseRepo->findbyId($id);
        $teachers = $userRepo->getTeachers();
        $categories = $categoryRepo->all();
        return view('Courses::edit', compact('course', 'teachers', 'categories'));

    }

    public function update($id, CourseRequest $request, CourseRepo $courseRepo)
    {
        $course = $courseRepo->findbyId($id);
        if ($request->hasFile('image')) {
            $request->request->add(['banner_id' => MediaFileService::upload($request->file('image'))->id]);
            $course->banner->delete();
        } else {
            $request->request->add(['banner_id' => $course->banner_id]);
        }
        $courseRepo->update($id, $request);
        return redirect(route('courses.index'));


    }

    public function destroy($id, CourseRepo $courseRepo)
    {
        $course = $courseRepo->findbyId($id);
        if ($course->banner) {
            $course->banner->delete();
        }
        $course->delete();
        return AjaxResponses::SuccessResponse();
    }

    public function accept($id, CourseRepo $courseRepo)
    {
        if ($courseRepo->updateConfimaitonStatus($id, Course::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailResponse();
    }
    public function reject($id, CourseRepo $courseRepo)
    {
        if ($courseRepo->updateConfimaitonStatus($id, Course::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailResponse();
    }
    public function lock($id, CourseRepo $courseRepo)
    {
        if ($courseRepo->updatStatus($id, Course::STATUS_LOCKED)) {
            return AjaxResponses::SuccessResponse();
        }
        return AjaxResponses::FailResponse();
    }

}
