<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>AI Assistant UangKu</h2>
        <span class="badge bg-primary fs-6"><i class="bi bi-robot"></i> Tanya Keuanganmu</span>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <p class="text-muted mb-0">Halo, <strong><?= explode(' ', $data['user']['name'])[0]; ?></strong>! Saya adalah AI UangKu. Saya bisa membantu merangkum dan menganalisis pengeluaran Anda. Coba tanyakan sesuatu!</p>
                </div>
                <div class="card-body">
                    <!-- Chat Box -->
                    <div id="chat-box" class="p-3 mb-3 bg-light rounded" style="height: 400px; overflow-y: auto;">
                        <div class="d-flex mb-3">
                            <div class="p-3 bg-primary text-white rounded-3 shadow-sm" style="max-width: 75%; border-top-left-radius: 0 !important;">
                                Silakan ketik pertanyaan Anda di bawah ini. Misalnya: "Berapa pengeluaran saya bulan ini?" atau "Kategori apa yang paling boros?"
                            </div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <form id="chat-form" class="d-flex gap-2">
                        <input type="text" id="chat-input" class="form-control" placeholder="Tanyakan seputar keuangan Anda..." required autocomplete="off">
                        <button type="submit" class="btn btn-primary px-4" id="btn-send">Kirim</button>
                    </form>
                    
                    <div id="loading" class="text-muted mt-2 d-none">
                        <small><em>AI sedang berpikir...</em></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatBox = document.getElementById('chat-box');
        const btnSend = document.getElementById('btn-send');
        const loading = document.getElementById('loading');

        function appendMessage(sender, text) {
            const wrapper = document.createElement('div');
            wrapper.className = 'd-flex mb-3 ' + (sender === 'user' ? 'justify-content-end' : '');
            
            let bgClass = sender === 'user' ? 'bg-white border' : 'bg-primary text-white shadow-sm';
            let borderRadius = sender === 'user' ? 'border-top-right-radius: 0 !important;' : 'border-top-left-radius: 0 !important;';
            
            // Format markdown-like bold (**) to HTML <strong> for AI responses
            let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            
            wrapper.innerHTML = `
                <div class="p-3 rounded-3 ${bgClass}" style="max-width: 75%; ${borderRadius}">
                    ${formattedText}
                </div>
            `;
            chatBox.appendChild(wrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const question = chatInput.value.trim();
            if (!question) return;

            // Tampilkan pesan user
            appendMessage('user', question);
            chatInput.value = '';
            chatInput.disabled = true;
            btnSend.disabled = true;
            loading.classList.remove('d-none');

            // Kirim ke backend
            fetch('<?= BASEURL; ?>/aiassistant/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    question: question,
                    csrf_token: '<?= CSRF::generate(); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    appendMessage('ai', data.answer);
                } else {
                    appendMessage('ai', 'Maaf, terjadi kesalahan: ' + data.message);
                }
            })
            .catch(error => {
                appendMessage('ai', 'Maaf, gagal terhubung ke server.');
                console.error(error);
            })
            .finally(() => {
                chatInput.disabled = false;
                btnSend.disabled = false;
                loading.classList.add('d-none');
                chatInput.focus();
            });
        });
    });
</script>
