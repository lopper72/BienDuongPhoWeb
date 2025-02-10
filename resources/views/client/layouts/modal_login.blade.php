<div id="modalLogIn" class="modal fade" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background-image: url({{asset('library/images/bg-login.png')}});">
            <div class="modal-header">
                <h4 class="modal-title">Đăng nhập</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formLogIn">
                    <div class="mb-3">
                        <label for="fmiEmail" class="form-label">Địa chỉ Email *</label>
                        <input type="text" class="form-control" id="fmiEmailLogIn" name="fmiEmailLogIn">
                    </div>
                    <div class="mb-4">
                        <label for="fmiPassword" class="form-label">Mật khẩu *</label>
                        <input type="password" class="form-control" id="fmiPasswordLogIn" name="fmiPasswordLogIn">
                    </div>
                    <div class="text-center">
                        <button class="btnSearch">Đăng nhập</button>
                    </div>
                    <div id="responseLogIn" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#formLogIn').on('submit', function(e){
            e.preventDefault();
            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('login') }}',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                type: 'POST',
                data: formData,
                success: function(response) {
                    location.href = "";
                },
                error: function(xhr) {
                    $('#responseLogIn').html('<p style="color: red; font-size:14px">' + xhr.responseJSON.message + '</p>');
                }
            });
        });
    });
</script>