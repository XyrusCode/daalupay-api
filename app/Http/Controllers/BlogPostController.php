<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Mail\NewBlogPost;
use DaaluPay\Models\BlogPost;
use DaaluPay\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BlogPostController extends BaseController
{
    public function getBlogPosts()
    {
        return $this->process(function () {
            $blogPosts = BlogPost::all();

            return $this->getResponse(
                status: true,
                message: 'Blog posts fetched successfully',
                data: $blogPosts,
                status_code: 200
            );
        });
    }

    public function getBlogPost($id)
    {
        return $this->process(function () use ($id) {
            $blogPost = BlogPost::find($id);

            return $this->getResponse(
                status: true,
                message: 'Blog post fetched successfully',
                data: $blogPost,
                status_code: 200
            );
        });
    }

    public function createBlogPost(Request $request)
    {
        return $this->process(function () use ($request) {
            $admin = auth('admin')->user() ?? auth('super_admin')->user();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'featuredImage' => 'required',
                'status' => 'required|string',
            ]);

            $blogPost = BlogPost::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'featured_image' => $validated['featuredImage'],
                'status' => $validated['status'],
                'author_id' => $admin->id,
            ]);

            // send mail to all users
            // $users = User::all();
            // foreach ($users as $user) {
            //     Mail::to($user->email)->send(new NewBlogPost($blogPost));
            // }

            return $this->getResponse(
                status: true,
                message: 'Blog post created successfully',
                data: $request->all(),
                status_code: 201
            );
        });
    }

    public function updateBlogPost(Request $request, $id)
    {
        return $this->process(function () use ($request, $id) {
            $blogPost = BlogPost::find($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'featuredImage' => 'required|string|max:255',
                'status' => 'required|string|in:true,false',
            ]);

            $blogPost->title = $validated['title'];
            $blogPost->content = $validated['content'];
            $blogPost->featured_image = $validated['featuredImage'] ?? '';
            $blogPost->status = $validated['status'];
            $blogPost->save();

            return $this->getResponse(
                status: true,
                message: 'Blog post updated successfully',
                data: $blogPost,
                status_code: 200
            );
        });
    }

    public function deleteBlogPost($id)
    {
        return $this->process(function () use ($id) {
            $blogPost = BlogPost::find($id);
            $blogPost->delete();
        });
    }

    public function updateStatus($id)
    {
        return $this->process(function () use ($id) {
            $blogPost = BlogPost::find($id);

            if ($blogPost->status == 'true') {
                $blogPost->update(['status' => 'false']);
            } else {
                $blogPost->update(['status' => 'true']);
            }
        });
    }

    public function getPublicBlogPosts()
    {
        return $this->process(function () {
            $blogPosts = BlogPost::where('status', 'true')->get();

            return $this->getResponse(
                status: true,
                message: 'Blog posts fetched successfully',
                data: $blogPosts,
                status_code: 200
            );
        });
    }
}
