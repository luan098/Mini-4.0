<script>
    if (<?= isset($_SESSION['toast']) ? 1 : 0 ?>)
        Toast.fire({
            icon: "<?= $_SESSION['toast']->icon ?? '' ?>",
            title: "<?= $_SESSION['toast']->title ?? '' ?>",
        });
</script>
</body>

</html>

<?php
unset($_SESSION['toast']);
?>