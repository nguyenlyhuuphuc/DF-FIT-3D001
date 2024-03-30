@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      @if (session('msg'))
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success" role="alert">
              {{ session('msg') }}
            </div>
          </div>
        </div>
      @endif

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>                  
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Category Name</th>
                        <th>Status</th>
                        <th style="width: 40px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $product)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $product->name }}</td>
                          <td>{{ $product->productCategory->name }}</td>
                          {{-- <td>{{ $product->product_category_name }}</td> --}}
                          <td>{{ $product->status ? 'Show' : 'Hide' }}</td>
                          <td>
                            <form action="{{ route('admin.product.destroy', ['product' => $product->id]) }}" method="post">
                              @method('delete')
                              @csrf
                              <button onclick="return confirm('Are you sure ?')" class="btn btn-danger" type="submit">Delete</button>
                            </form>
                            
                            @if($product->trashed())
                            <form action="{{ route('admin.product.restore', ['product' => $product->id]) }}" method="post">
                              @csrf
                              <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                            @endif


                            <a href="{{ route('admin.product_category.detail', ['id' => $product->id]) }}" class="btn btn-primary">Edit</a>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                  {{ $products->links() }}
                </div>
              </div>
            </div>
            <!-- /.col -->
          </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection