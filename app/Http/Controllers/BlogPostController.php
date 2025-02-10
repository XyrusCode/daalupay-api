<?php

namespace DaaluPay\Http\Controllers;

use DaaluPay\Mail\NewBlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DaaluPay\Models\BlogPost;
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
                // 'featured_image' => 'required|file|mimes:jpeg,png,jpg|max:2048',
                'status' => 'required|string',
            ]);

            // $imagePath = $request->file('featured_image')->store('blog-images', 'public');

            $blogPost = BlogPost::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'featured_image' => '',
                'status' => $validated['status'],
                'author_id' => $admin->id,
            ]);

            Mail::to($admin->email)->send(new NewBlogPost( $blogPost));

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
                // 'featured_image' => 'required|string|max:255',
                'status' => 'required|string|in:true,false',
            ]);

            $blogPost->title = $validated['title'];
            $blogPost->content = $validated['content'];
            $blogPost->featured_image = $validated['featured_image'] ?? '';
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
