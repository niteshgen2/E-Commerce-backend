@extends('layouts.admin')

@section('content')
<div class="wrapper">

    @include('admin.auth.header')
    @include('admin.auth.sidebar')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <h1>Add Product</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Detail</label>
                        <textarea name="detail" class="form-control" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Product</button>

                </form>

            </div>
        </section>

    </div>

    @include('admin.auth.footer')

</div>
@endsection
