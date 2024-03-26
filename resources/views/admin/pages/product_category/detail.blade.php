@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Product Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product Category</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <ul>
          @foreach($errors->all() as $item)
            <li> {{ $item }}</li>
          @endforeach
        </ul>
        <div class="row">
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Product Category</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="{{ route('admin.product_category.update', ['id' => $productCategory->id]) }}" method="POST">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input value="{{ old('name') ?? $productCategory->name }}" type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                      @error('name')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="slug">Slug</label>
                      <input value="{{ old('slug') ?? $productCategory->slug }}" type="text" name="slug" class="form-control" id="slug" placeholder="Enter slug">
                      @error('slug')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select name="status" class="custom-select" id="status">
                        <option value="">---Please Select---</option>
                        <option {{ (old('status') ?? $productCategory->status) == '1' ? 'selected' : '' }} value="1">Show</option>
                        <option {{ (old('status') ?? $productCategory->status) == '0' ? 'selected' : '' }} value="0">Hide</option>
                      </select>
                      @error('status')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                      </div>
                  </div>
                  <!-- /.card-body -->
                  @csrf
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('my-script')
<script>
  $(document).ready(function (){
    $('#name').on('keyup', function(){
        var nameInput = $(this).val();
        
        $.ajax({
           method: "POST", //method of form
           url: "{{ route('admin.product_category.slug') }}", //action of form
           data: { name: nameInput, _token: "{{ csrf_token() }}" }, //input name
           success: function (response){
              $('#slug').val(response.slug);
           },
           fail: function(){
              alert('Something went wrong with ajax slug');
           }
        });
    });
  });
</script>
@endsection