@if(Auth::check())
    <div class="commentContent">
        <div class="avatarUser">
            <img src="{{asset('library/images/avatar-user.jpg')}}" alt="Avatar User" class="object-fit-cover w-100 h-100">
        </div>
        <form id="formComment">
            <div class="starRating">
                <span class="starIcon" data-value="1"><i class="fa-solid fa-star"></i></span>
                <span class="starIcon" data-value="2"><i class="fa-solid fa-star"></i></span>
                <span class="starIcon" data-value="3"><i class="fa-solid fa-star"></i></span>
                <span class="starIcon" data-value="4"><i class="fa-solid fa-star"></i></span>
                <span class="starIcon" data-value="5"><i class="fa-solid fa-star"></i></span>
                <input type="hidden" id="fmiRate" name="fmiRate" value="0">
            </div>
            <textarea name="fmiCommentText" id="fmiCommentText" rows="4"></textarea>
            <input type="hidden" name="fmiProductId" value="{{$product->id}}">
            <input type="hidden" name="fmiUserId" value="{{Auth::user()->id}}">
            <button class="btnSearch">Gửi bình luận</button>
        </form>
    </div>
    <div id="modalComment" class="modal fade" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="messegeComment" class="text-center"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const stars = document.querySelectorAll('.starIcon');
        const fmiRate = document.getElementById('fmiRate');
        
        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const value = star.getAttribute('data-value');
                updateStars(value);
            });
            star.addEventListener('mouseleave', () => {
                if (fmiRate.value == 0) {
                    const selectedValue = document.querySelector('.star.selected')?.getAttribute('data-value');
                    updateStars(selectedValue || 0);
                }
            });
            star.addEventListener('click', () => {
                const value = star.getAttribute('data-value');
                updateStars(value);
                fmiRate.value = value;
            });
        });

        function updateStars(value) {
            stars.forEach(star => {
                const starValue = star.getAttribute('data-value');
                if (starValue <= value) {
                    star.classList.add('selected');
                } else {
                    star.classList.remove('selected');
                }
            });
        }

        $('#formComment').on('submit', function(e){
            e.preventDefault();
            var formData = $(this).serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            const params = new URLSearchParams(formData);
            if(params.get('fmiRate') == 0){
                alert('Vui lòng đánh giá sao!');
                return;
            }
            if(params.get('fmiCommentText') == ''){
                alert('Vui lòng nhập bình luận!');
                return;
            }
            $.ajax({
                url: '{{ route('comment') }}',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#messegeComment').html('<span style="color: green; font-size:18px">' + response.message + '</span>');
                    }else{
                        $('#messegeComment').html('<span style="color: red; font-size:18px">'+ response.message +'</span>');
                    }
                    var myModal = new bootstrap.Modal(document.getElementById('modalComment'));
                    myModal.show();
                    setTimeout(function() { 
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        });
    </script>
@else
    <p>Vui lòng đăng nhập để đánh giá!</p>
@endif