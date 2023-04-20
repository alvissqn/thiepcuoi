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
                        <div class="mb-2">
                            <div class="form-group position-relative input-label ">
                                <label class="">Tên Mẫu Thiệp</label>
                                <input class="form-control " type="text" name="settings__style_font_size" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="">

                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="form-group position-relative ">
                                <label class="">Hình đại diện (thumbnail)</label>
                                <input class="form-control" type="file" name="settings__style_font_size" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="">

                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="form-group position-relative">
                                <label class="">File Mẫu (json)</label>
                                <input class="form-control " type="file" name="settings__style_font_size" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="">

                            </div>
                        </div>
                        <div class="mb-2">
                            <button type="button" class="btn btn-primary form-save-btn">
                                <i class="bx bx-save"></i>
                                {{ __('admin/language.save_button_label') }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


