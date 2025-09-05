document.addEventListener('DOMContentLoaded', function() {
    const commentsContainer = document.getElementById('comments');
    const commentForm = document.getElementById('comment-form');
    
    // Function to load comments
    function loadComments() {
        fetch('/api/comments.php')
            .then(response => response.json())
            .then(comments => {
                if (comments.length === 0) {
                    commentsContainer.innerHTML = '<p>No comments yet. Be the first to comment!</p>';
                } else {
                    let html = '';
                    comments.forEach(comment => {
                        // XSS VULNERABILITY: Direct insertion of user content without sanitization
                        html += `
                            <div class="comment mb-3">
                                <h5>${comment.name}</h5>
                                <p>${comment.comment}</p>
                                <small class="text-muted">Posted on ${comment.date}</small>
                            </div>
                            <hr>
                        `;
                        
                        /* 
                        // GÜVENLİ VERSİYON: JavaScript ile içeriği güvenli bir şekilde işleme
                            const safeName = document.createTextNode(comment.name).textContent;
                            const safeComment = document.createTextNode(comment.comment).textContent;
                            const safeDate = document.createTextNode(comment.date).textContent; 
                            html += `
                                <div class="comment mb-3">
                                    <h5>${safeName}</h5>
                                    <p>${safeComment}</p>
                                    <small class="text-muted">Posted on ${safeDate}</small>
                                </div>
                                <hr>
                        `;
                        */
                    });
                    commentsContainer.innerHTML = html;
                }
            })
            .catch(error => {
                commentsContainer.innerHTML = '<p class="text-danger">Error loading comments</p>';
                console.error('Error:', error);
            });
    }
    
    loadComments();
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nameInput = document.getElementById('name');
            const commentInput = document.getElementById('comment');
            
            const data = {
                name: nameInput.value,
                comment: commentInput.value
            };
            
            fetch('/api/comments.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    nameInput.value = '';
                    commentInput.value = '';
                    
                    loadComments();
                } else {
                    alert('Error: ' + result.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting your comment.');
            });
        });
    }
});
