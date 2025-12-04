document.addEventListener('DOMContentLoaded', function () {
    const favoriteButtons = document.querySelectorAll('.btn-favorite');

    favoriteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            
            if (!productId) {
                console.error('Product ID not found!');
                return;
            }

            const url = `index.php?ctrl=favorite&act=toggle&id=${productId}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Toggle the visual state
                        this.classList.toggle('is-favorited');
                        
                        // You can also show a small notification if you want
                        // For example:
                        // alert(data.message);

                    } else {
                        // If user is not logged in or another error occurs
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
                });
        });
    });
});
