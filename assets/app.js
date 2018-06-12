(() => {
    const $fixers = document.querySelectorAll('[data-fixer]');
    const $query = document.getElementById('query');

    function filterFixers(query) {
        $fixers.forEach(($fixer) => {
            if ($fixer.dataset.name.includes(query)) {
                $fixer.style.display = 'block';
            } else {
                $fixer.style.display = 'none';
            }
        });
    }

    $query.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    $query.addEventListener('keyup', () => filterFixers($query.value));
})();
