<style>
footer.sticky-footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    padding: 5px 0;
    background-color:var(--bs-primary);
    z-index: 100;
    color: white;
}

</style>
<footer class="sticky-footer">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            {{ date('Y') }} © {{ config('brand.short_name') }} All rights reserved
        </div>
    </div>
</footer>
