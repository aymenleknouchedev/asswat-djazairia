@extends('layouts.admin')

@section('title', 'أصوات جزائرية | إرسال بريد')

@section('content')
    <div class="nk-app-root">
        <div class="nk-main">
            @include('dashboard.components.sidebar')
            <div class="nk-wrap">
                @include('dashboard.components.header')

                <div class="nk-content">
                    <div class="container">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title" data-en="Send Mail" data-ar="إرسال بريد">إرسال بريد</h4>
                                <p data-en="Fill the form below to send a new email."
                                   data-ar="املأ النموذج أدناه لإرسال بريد إلكتروني جديد.">
                                   املأ النموذج أدناه لإرسال بريد إلكتروني جديد.
                                </p>
                            </div>
                        </div>

                        <div class="nk-block">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <form id="sendMailForm" enctype="multipart/form-data" class="form-validate">
                                        @csrf

                                        {{-- Email To --}}
                                        <div class="form-group">
                                            <label class="form-label" data-en="Recipient Email" data-ar="البريد المرسل إليه">البريد المرسل إليه</label>
                                            <div class="form-control-wrap">
                                                <input type="email" name="email" class="form-control" placeholder="example@email.com" value="{{ $email }}" required @if($email) disabled @endif>
                                            </div>
                                        </div>

                                        {{-- Subject --}}
                                        <div class="form-group">
                                            <label class="form-label" data-en="Subject" data-ar="الموضوع">الموضوع</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="subject" class="form-control" placeholder="عنوان الرسالة" required>
                                            </div>
                                        </div>

                                        {{-- Message with Quill --}}
                                        <div class="form-group">
                                            <label class="form-label" data-en="Message" data-ar="الرسالة">الرسالة</label>
                                            <div class="form-control-wrap">
                                                <div id="editor" style="min-height: 200px;"></div>
                                                <textarea name="body" id="body" class="d-none"></textarea>
                                            </div>
                                        </div>

                                        {{-- Attachment --}}
                                        <div class="form-group">
                                            <label class="form-label" data-en="Attachments" data-ar="المرفقات">المرفقات</label>
                                            <div class="form-control-wrap">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="attachments[]" multiple>
                                                    <label class="custom-file-label">اختر ملفات (يمكنك رفع أكثر من ملف)</label>
                                                </div>
                                                <small class="form-text text-muted" data-en="Allowed formats: pdf, docx, jpg, png" data-ar="الصيغ المسموحة: pdf, docx, jpg, png">
                                                    الصيغ المسموحة: pdf, docx, jpg, png
                                                </small>
                                            </div>
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <em class="icon ni ni-send"></em>
                                                <span data-en="Send Mail" data-ar="إرسال البريد">إرسال البريد</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- .nk-block -->
                    </div>
                </div>

                @include('dashboard.components.footer')
            </div>
        </div>
    </div>
@endsection

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: '',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Copy HTML content into hidden textarea before submit
        document.getElementById('sendMailForm').onsubmit = function (e) {
            e.preventDefault();
            document.querySelector('#body').value = quill.root.innerHTML;
            const files = document.querySelector('input[name="attachments[]"]').files;

            const formData = new FormData();
            formData.append('email', this.email.value);
            formData.append('subject', this.subject.value);
            formData.append('body', this.body.value);

            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    formData.append('attachments[]', files[i]);
                }
            }

            fetch("{{ route('dashboard.mail.send-mail.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      this.reset();
                      quill.setContents([]);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            confirmButtonText: 'OK'
                        });

                  } else if (data.errors) {
                      const messages = Object.values(data.errors).flat().join('\n');
                      Swal.fire({
                          icon: 'error',
                          title: 'خطأ في البيانات',
                          text: messages,
                          confirmButtonText: 'OK'
                      });
                  } else if (data.error) {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: data.error,
                          confirmButtonText: 'OK'
                      });
                  }
              }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while sending the email.',
                    confirmButtonText: 'OK'
                });
              });
            return false;
        };
    });
</script>

