<!-- 削除確認モーダル（軽量版） -->
<div id="delete-confirm-modal" style="display:none;">
    <div style="position:fixed;inset:0;background:rgba(0,0,0,0.4);"></div>
    <div style="position:fixed;left:50%;top:50%;transform:translate(-50%,-50%);background:#fff;border-radius:12px;padding:1.25rem;max-width:420px;width:90%;box-shadow:0 6px 24px rgba(2,6,23,0.2);">
        <div style="font-weight:800;margin-bottom:0.5rem;">削除の確認</div>
        <p style="color:#374151;margin-bottom:1rem;">このメッセージを本当に削除しますか？操作は取り消せません。</p>
        <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
            <button type="button" class="btn-cancel" style="padding:0.5rem 0.75rem;border-radius:8px;border:1px solid #e5e7eb;background:#fff;">キャンセル</button>
            <button type="button" class="btn-confirm" style="padding:0.5rem 0.75rem;border-radius:8px;border:none;background:#dc2626;color:#fff;font-weight:700;">削除する</button>
        </div>
    </div>
</div>

<script>
    // モーダルを使う実装に切り替える場合に備えて、最小限の振る舞いを用意します。
    (function(){
        const modal = document.getElementById('delete-confirm-modal');
        if(!modal) return;
        const btnCancel = modal.querySelector('.btn-cancel');
        const btnConfirm = modal.querySelector('.btn-confirm');
        let pendingForm = null;

        document.addEventListener('click', e => {
            const trigger = e.target.closest('.delete-trigger');
            if (trigger) {
                e.preventDefault();
                pendingForm = trigger.closest('form');
                // 現在のコードベースでは confirm() を使っているため、既存挙動を維持します。
                if (confirm('本当に削除しますか？')) {
                    pendingForm.submit();
                } else {
                    pendingForm = null;
                }
            }
        });

        btnCancel && btnCancel.addEventListener('click', () => {
            modal.style.display = 'none';
            pendingForm = null;
        });
        btnConfirm && btnConfirm.addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
        });
    })();
</script>
