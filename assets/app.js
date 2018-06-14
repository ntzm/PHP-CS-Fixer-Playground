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

    document.querySelectorAll('[data-option-add]').forEach(($addOption) => {
        $addOption.addEventListener('click', (event) => {
            event.preventDefault();

            const fixer = $addOption.dataset.optionFixer;
            const name = $addOption.dataset.optionName;

            const $group = document.createElement('div');
            $group.classList.add('form-group', 'input-group');

            const $input = document.createElement('input');
            $input.classList.add('form-control');
            $input.setAttribute('type', 'text');
            $input.setAttribute('name', `fixers[${fixer}][${name}][]`);

            const $removeContainer = document.createElement('div');
            $removeContainer.classList.add('input-group-append');

            const $removeOption = document.createElement('button');
            $removeOption.textContent = '×';
            $removeOption.classList.add('btn', 'btn-outline-danger');

            $removeOption.addEventListener('click', (event) => {
                event.preventDefault();

                $removeOption.parentNode.parentNode.remove();
            });

            $removeContainer.appendChild($removeOption);

            if ($addOption.dataset.optionAdd === 'associative') {
                const $keyInput = document.createElement('input');
                $keyInput.classList.add('form-control');

                $group.append($keyInput);
            }

            $group.appendChild($input);
            $group.appendChild($removeContainer);

            $addOption.parentNode.insertBefore($group, $addOption);
        });
    });

    document.querySelectorAll('[data-option-remove]').forEach(($removeOption) => {
        $removeOption.addEventListener('click', (event) => {
            event.preventDefault();

            $removeOption.parentNode.parentNode.remove();
        });
    });
})();
