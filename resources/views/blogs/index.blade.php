<!-- write to show all blogs in table format -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Blogs') }}</div>

                <div class="card-body">
                    <h2 class="mb-4">All Blogs</h2>
                    <!-- check if it is auth -->
                    @auth
                    <div class="mb-3">
                        <a href="{{ route('blogs.create') }}" class="btn btn-primary">Create New Blog</a>
                    </div>
                    @endauth

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blogs as $blog)
                                <tr>
                                    <td>{{ $blog->title }}</td>
                                    <td>{{ Str::limit($blog->content, 100) }}</td>
                                    <td>{{ $blog->user->name }}</td>
                                    <td>
                                        @if ($blog->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($blog->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif ($blog->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        @guest
                                        <a href="{{ route('blogs.detail', $blog->id) }}" class="btn btn-info btn-sm">View</a>
                                        @endguest
                                        @auth
                                            <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-info btn-sm">View</a>
                                            @if (Auth::user()->id === $blog->user_id)
                                                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            @endif
                                            @if (Auth::user()->role === 'admin')
                                                @if ($blog->status === 'pending')
                                                <form action="{{ route('blogs.change-status', ['id' => $blog->id, 'status' => 'published']) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-success btn-sm">Publish</button>
                                                </form>
                                               <form action="{{ route('blogs.change-status', ['id' => $blog->id, 'status' => 'rejected']) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                                </form>
                                                @elseif ($blog->status === 'published')
                                                <form action="{{ route('blogs.change-status', ['id' => $blog->id, 'status' => 'pending']) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-warning btn-sm">Unpublish</button>
                                                </form>
                                                @endif
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
