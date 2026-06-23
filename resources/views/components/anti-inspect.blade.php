<!-- Script Anti-Inspect (Keamanan UI) -->
<script>
    // Matikan klik kanan
    document.addEventListener('contextmenu', event => event.preventDefault());
    
    // Matikan F12 dan kombinasi Inspect
    document.onkeydown = function(e) {
        if(e.keyCode == 123) return false;
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) return false;
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) return false;
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) return false;
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) return false;
    }
</script>
