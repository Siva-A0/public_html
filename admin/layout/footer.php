	</div> <!-- content-area -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    var left = document.getElementById('content_left');
    if (!left) {
        return;
    }

    var computed = window.getComputedStyle(left);
    var isHidden = computed && (computed.display === 'none' || computed.visibility === 'hidden');
    var hasContent = left.children.length > 0 || (left.textContent || '').trim().length > 0;

    if (isHidden || !hasContent) {
        document.body.classList.add('admin-no-content-left');
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
