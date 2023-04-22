@extends('layouts.admin')
@section('header')
    @parent
@endsection
@section('content')
    <main class="row">
        <div class="mt-2 col-md-12">
            <div class="card">
                <div class="card-header row align-items-center">
                    <h4 class="card-title">Thêm Mẫu Thiệp Cưới</h4>
                </div>
                <hr>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form action="{!! route('admin.save_template') !!}" id="ftemplate" name="ftemplate" method="post">
                        <div class="mb-2">
                            <div class="form-group position-relative input-label ">
                                <label class="">Tên Mẫu Thiệp</label>
                                <input class="form-control " type="text" name="editor_name_template" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="">

                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="form-group position-relative ">
                                <label class="">Hình đại diện (thumbnail)</label>
                                <input class="form-control" type="file" name="editor_thumbnail_template" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="">

                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="form-group position-relative">
                                <label class="">File Mẫu (json)</label>
                                <input class="form-control " type="file" name="editor_file_template" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="">

                            </div>
                        </div>
                        <div class="mb-2">
                            <button type="button" class="btn btn-primary form-save-btn" id="form-save-btn">
                                <i class="bx bx-save"></i>
                                {{ __('admin/language.save_button_label') }}
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('footer')
    @parent
@endsection
@section('footer-assets')
@parent
    <script>
        $(document).ready(function(){
            $("#form-save-btn").on('click',function(){
                var faction =  $("#ftemplate").attr('action');
                // var fdata = $("#fstory").serialize();
                var fdata = new FormData($("#ftemplate")[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: fdata,
                    url: faction,
                    type: 'POST',
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data){
                        $('#ftemplate')[0].reset();
                        console.log(data);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
        });


    </script>
@endsection

