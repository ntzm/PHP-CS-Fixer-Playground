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

    function addRemoveEventListener($removeOption) {
        $removeOption.addEventListener('click', (event) => {
            event.preventDefault();

            $removeOption.parentNode.parentNode.remove();
        });
    }

    document.querySelectorAll('[data-option-add]').forEach(($addOption) => {
        $addOption.addEventListener('click', (event) => {
            event.preventDefault();

            const fixer = $addOption.dataset.optionFixer;
            const name = $addOption.dataset.optionName;

            const $group = document.createElement('div');
            $group.classList.add('form-group', 'input-group');

            const $removeContainer = document.createElement('div');
            $removeContainer.classList.add('input-group-append');

            const $removeOption = document.createElement('button');
            $removeOption.textContent = '×';
            $removeOption.classList.add('btn', 'btn-outline-danger');

            addRemoveEventListener($removeOption);

            $removeContainer.appendChild($removeOption);

            if ($addOption.dataset.optionAdd === 'associative') {
                const $keyInput = document.createElement('input');
                $keyInput.classList.add('form-control');
                $keyInput.setAttribute('type', 'text');
                $keyInput.setAttribute('name', `fixers[${fixer}][${name}][_keys][]`);

                const $valueInput = document.createElement('input');
                $valueInput.classList.add('form-control');
                $valueInput.setAttribute('type', 'text');
                $valueInput.setAttribute('name', `fixers[${fixer}][${name}][_values][]`);

                $group.append($keyInput);
                $group.append($valueInput);
            } else {
                const $valueInput = document.createElement('input');
                $valueInput.classList.add('form-control');
                $valueInput.setAttribute('type', 'text');
                $valueInput.setAttribute('name', `fixers[${fixer}][${name}][]`);

                $group.append($valueInput);
            }

            $group.appendChild($removeContainer);

            $addOption.parentNode.insertBefore($group, $addOption);
        });
    });

    document.querySelectorAll('[data-option-remove]').forEach(($removeOption) => {
        addRemoveEventListener($removeOption);
    });
})();
