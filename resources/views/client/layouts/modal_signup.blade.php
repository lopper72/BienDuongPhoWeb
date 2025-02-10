<div id="modalSignUp" class="modal fade" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background-image: url({{asset('library/images/bg-login.png')}});">
            <div class="modal-header">
                <h4 class="modal-title">Đăng ký</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSignUp">
                    <div class="mb-3">
                        <label for="fmiNameSignUp" class="form-label">Tên *</label>
                        <input type="text" class="form-control" id="fmiNameSignUp" name="fmiNameSignUp">
                    </div>
                    <div class="mb-3">
                        <label for="fmiEmailSignUp" class="form-label">Địa chỉ Email *</label>
                        <input type="email" class="form-control" id="fmiEmailSignUp" name="fmiEmailSignUp">
                    </div>
                    <div class="mb-4">
                        <label for="fmiPasswordSignUp" class="form-label">Mật khẩu *</label>
                        <input type="password" class="form-control" id="fmiPasswordSignUp" name="fmiPasswordSignUp">
                    </div>
                    <div class="text-center">
                        <button class="btnSearch">Đăng ký</button>
                    </div>
                    <div id="responseSignUp" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#formSignUp').on('submit', function(e){
            e.preventDefault();
            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('signup') }}',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                type: 'POST',
                data: formData,
                success: function(response) {
                    location.href = "";
                },
                error: function(xhr) {
                    $('#responseSignUp').html('<p style="color: red; font-size:14px;">' + xhr.responseJSON.message + '</p>');
                }
            });
        });
    });
</script>