document.addEventListener('DOMContentLoaded', function() {
  console.log('Script loaded!'); // Debug message to ensure script runs

  const dropdowns = document.querySelectorAll('.custom-dropdown');
  console.log('Found dropdowns:', dropdowns.length); // Log how many dropdowns we found

  dropdowns.forEach(dropdown => {
      const selectedOption = dropdown.querySelector('.selected-option');
      const optionsList = dropdown.querySelector('.options-list');
      const hiddenInput = dropdown.querySelector('input[type="hidden"]');

      selectedOption.addEventListener('click', () => {
          console.log('Selected option clicked!'); // Debug message
          optionsList.classList.toggle('hidden');
      });

      optionsList.addEventListener('click', (event) => {
          if(event.target.tagName.toLowerCase() === 'li' && event.target.dataset.value !== undefined) {
              console.log('List item clicked:', event.target.textContent); // Debug message
              selectedOption.textContent = event.target.textContent;
              selectedOption.dataset.selectedValue = event.target.dataset.value;
              hiddenInput.value = event.target.dataset.value;
              optionsList.classList.add('hidden');
          }
      });
  });

  document.addEventListener('click', (event) => {
      if(!event.target.closest('.custom-dropdown')) {
          console.log('Clicked outside of dropdown'); // Debug message
          document.querySelectorAll('.options-list').forEach(list => list.classList.add('hidden'));
      }
  });
});