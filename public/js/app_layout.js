$(document).ready(function() {
    // Toggle Sidebar
    $('#sidebarToggle').on('click', function() {
        $('#sidebar').addClass('active');
        $('#sidebarOverlay').addClass('active');
    });

    // Close Sidebar on Overlay Click
    $('#sidebarOverlay').on('click', function() {
        $('#sidebar').removeClass('active');
        $(this).removeClass('active');
    });

    // Automatically wrap all table-custom to make them scrollable horizontally on mobile
    $('.table-custom').wrap('<div class="table-responsive"></div>');

    // Submenu Toggle
    $('.submenu-toggle').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        $(target).slideToggle(300);
        $(this).find('.bi-chevron-down').toggleClass('rotate-180');
    });
});
