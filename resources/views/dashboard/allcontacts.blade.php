@extends('layouts.admin')

@section('title', 'أصوات جزائرية | البريد الإلكتروني')

@section('content')
<div class="nk-app-root">
    <div class="nk-main">
        @include('dashboard.components.sidebar')

        <div class="nk-wrap">
            @include('dashboard.components.header')

            <div class="nk-content">
                <div class="container">

                    <div class="nk-block nk-block-lg">
                        <!-- Header -->
                        <div class="nk-block-head mb-4">
                            <div class="nk-block-head-content">
                                <h4 class="nk-block-title fw-bold text-primary" data-en="Contact messages" data-ar="البريد الإلكتروني">
                                    <em class="icon ni ni-inbox me-1"></em> البريد الإلكتروني
                                    @if ($pendingCount)
                                        <span class="badge bg-danger ms-1">{{ $pendingCount }}</span>
                                    @endif
                                </h4>
                                <p class="text-muted" data-en="All messages sent from the contact form" data-ar="جميع الرسائل المرسلة من نموذج اتصل بنا">
                                    جميع الرسائل المرسلة من نموذج اتصل بنا
                                </p>
                            </div>
                        </div>

                        <!-- Success / Error Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-icon mb-3">
                                <em class="icon ni ni-check-circle"></em>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-icon mb-3">
                                <em class="icon ni ni-cross-circle"></em>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <div class="modal fade" id="viewContactModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="contactSubject" data-ar="الرسالة" data-en="Message">الرسالة</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-muted mb-2" id="contactMeta"></p>
                                        <p class="text-dark" id="contactMessageContent" style="white-space: pre-wrap;"></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" data-ar="إغلاق" data-en="Close">إغلاق</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contacts Table -->
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <form method="GET" action="{{ route('dashboard.contacts') }}" class="row g-2">
                                    <div class="col-md-8 col-12">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="form-control"
                                            placeholder="ابحث بالاسم أو البريد أو الموضوع..."
                                            data-en="Search by name, email or subject..."
                                            data-ar="ابحث بالاسم أو البريد أو الموضوع...">
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <select name="status" class="form-select">
                                            <option value="" data-ar="كل الحالات" data-en="All statuses">كل الحالات</option>
                                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>جديدة</option>
                                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>مقروءة</option>
                                            <option value="responded" {{ request('status') === 'responded' ? 'selected' : '' }}>تم الرد</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 col-6">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <em class="icon ni ni-search"></em>
                                        </button>
                                    </div>
                                    <div class="col-md-1 col-6">
                                        <a href="{{ route('dashboard.contacts') }}" class="btn btn-light w-100">
                                            <em class="icon ni ni-undo"></em>
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="card-inner p-0">
                                <table class="table table-orders mb-0">
                                    <thead class="tb-odr-head bg-light">
                                        <tr class="tb-odr-item">
                                            <th data-ar="المرسل" data-en="Sender">المرسل</th>
                                            <th data-ar="البريد الإلكتروني" data-en="Email">البريد الإلكتروني</th>
                                            <th data-ar="الموضوع" data-en="Subject">الموضوع</th>
                                            <th data-ar="الرسالة" data-en="Message">الرسالة</th>
                                            <th data-ar="تاريخ الإرسال" data-en="Sent at">تاريخ الإرسال</th>
                                            <th data-ar="الحالة" data-en="Status">الحالة</th>
                                            <th data-ar="الإجراءات" data-en="Actions" class="text-center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tb-odr-body">
                                        @forelse ($contacts as $contact)
                                            <tr class="tb-odr-item">
                                                <td><strong>{{ trim($contact->first_name . ' ' . $contact->last_name) }}</strong></td>
                                                <td class="text-muted">{{ $contact->email }}</td>
                                                <td>{{ Str::limit($contact->subject, 40) }}</td>
                                                <td>{{ Str::limit($contact->description, 50) }}</td>
                                                <td>{{ $contact->created_at?->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <select class="form-select form-select-sm contact-status-select" data-id="{{ $contact->id }}">
                                                        <option value="pending" {{ $contact->status == 'pending' ? 'selected' : '' }}
                                                            data-ar="جديدة" data-en="New">جديدة</option>
                                                        <option value="read" {{ $contact->status == 'read' ? 'selected' : '' }}
                                                            data-ar="مقروءة" data-en="Read">مقروءة</option>
                                                        <option value="responded" {{ $contact->status == 'responded' ? 'selected' : '' }}
                                                            data-ar="تم الرد" data-en="Responded">تم الرد</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info btn-sm view-contact-btn"
                                                        data-subject="{{ $contact->subject }}"
                                                        data-meta="{{ trim($contact->first_name . ' ' . $contact->last_name) }} — {{ $contact->email }} — {{ $contact->created_at?->format('Y-m-d H:i') }}"
                                                        data-message="{{ $contact->description }}">
                                                        <em class="icon ni ni-eye"></em>
                                                    </button>
                                                    <a href="{{ route('dashboard.mail.send-mail', ['email' => $contact->email]) }}" class="btn btn-success btn-sm">
                                                        <em class="icon ni ni-mail"></em>
                                                    </a>
                                                    <form action="{{ route('dashboard.contacts.delete', $contact->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <em class="icon ni ni-trash"></em>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-3">
                                                    <em class="icon ni ni-info me-1"></em> لا توجد رسائل
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $contacts->links() }}
                        </div>
                    </div>
                </div>
            </div>

            @include('dashboard.components.footer')
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.view-contact-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('contactSubject').innerText = this.getAttribute('data-subject');
                document.getElementById('contactMeta').innerText = this.getAttribute('data-meta');
                document.getElementById('contactMessageContent').innerText = this.getAttribute('data-message');
                let modal = new bootstrap.Modal(document.getElementById('viewContactModal'));
                modal.show();
            });
        });

        document.querySelectorAll('.contact-status-select').forEach(function (select) {
            select.addEventListener('change', function () {
                let id = this.getAttribute('data-id');
                let status = this.value;

                fetch("{{ url('/dashboard/contact') }}/" + id + "/update-status", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.error || "Something went wrong");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Failed to update status");
                });
            });
        });
    });
</script>
