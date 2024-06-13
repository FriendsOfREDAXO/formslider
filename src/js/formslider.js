export default function formSlider(
  options = {}
) {

  // set default options
  options = Object.assign({
    selector: '.formslider',
    showProgress: true,
    showProgressCount: false,
    nextButtonLabel: 'Next',
    nextButtonClasses: '',
    prevButtonLabel: 'Prev',
    prevButtonClasses: '',
    deleteDivClasses: '',
    deleteDataButtonLabel: 'Delete my data',
    deleteDataButtonClasses: '',
    deleteDataHeadline: '<h6>Delete data</h6>',
    deleteDataText: '<p>Your data is stored locally in your browser. If you want to delete your data, you can do so by clicking the button below. (If you are on a public computer, you should delete your data.)</p>',
    prependProgress: false,
    progressClickable: true
  }, options);

  // add option to override options by data-attributes

  document.querySelectorAll(options.selector).forEach(function (element) {
    // set anchorId for scrollto on buttons
    let anchorId = element.id;

    // initiate starting values
    let currentFieldset = 0;

    // find form
    const form = element.querySelector('form');

    // find fieldsets
    const fieldsets = element.querySelectorAll('fieldset');

    // count fieldsets
    let fieldsetCount = fieldsets.length;

    let inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(function (input) {
      let label = findLabelForControl(input);
      if(input.attributes.required) {
        label.classList.add('required');
      }

      input.addEventListener('focus', function() {
        let label = findLabelForControl(input);
        if(label) {
          label.classList.add('active');
        }
      });

      input.addEventListener('blur', function() {
        let label = findLabelForControl(input);
        if(label && input.value === '') {
          label.classList.remove('active');
        }
      });
    });

    // load data from local storage
    let storedData = localStorage.getItem(form.id);
    if(storedData) {
      let formObj = JSON.parse(storedData);
      for (let key in formObj) {
        let control = form.querySelector('[name="' + key + '"]');
        if(control) {
          if(control.type === 'checkbox') {
            control.checked = true;
          } else if(control.type === 'radio') {
            let radios = form.querySelectorAll('[name="' + key + '"][value="' + formObj[key] + '"]');
            for (let radio of radios) {
              if(radio.value === formObj[key]) {
                radio.checked = true;
              }
            }
          } else {
            control.value = formObj[key];
          }
        }
      }
    }

    // add progressbar under whole form
    if(options.showProgress) {
      var progress = document.createElement('div');
      progress.classList.add('form-progress');
      if(options.prependProgress) {
        element.prepend(progress);
      } else {
        element.appendChild(progress);
      }

      // add progressbar steps
      fieldsets.forEach(function (fieldset, index) {
        var step = document.createElement('div');
        step.classList.add('form-progress-step');
        step.setAttribute('data-stepcount', index);
        if(options.progressClickable) {
          step.classList.add('clickable');
          step.addEventListener('click', function() {
            currentFieldset = index;
            currentFieldset = jumpToFieldset(currentFieldset, form, prevButton, nextButton, fieldsetCount, index);
            currentStep = updateCurrentStep(progress, currentStep, currentFieldset, element, form);
            document.getElementById(anchorId).scrollIntoView({
              behavior: 'smooth'
            });
          });
        }
        if(options.showProgressCount) {
          step.innerHTML = index + 1;
        }
        progress.appendChild(step);
      });
    }

    let currentStep = progress.querySelector('[data-stepcount="' + currentFieldset + '"]');

    // setup navigation buttons area
    var nav = document.createElement('div');
    nav.classList.add('formslider-nav');
    element.appendChild(nav);

    // add prev button under whole form
    var prevButton = document.createElement('button');
    prevButton.innerHTML = options.prevButtonLabel;
    prevButton.classList.add('form-prev-button');
    if(options.prevButtonClasses) {
      let classes = options.prevButtonClasses.split(' ');
      classes.forEach(function (className) {
        prevButton.classList.add(className);
      });
    }
    nav.appendChild(prevButton);

    //hide button initially
    if(currentFieldset === 0) {
      prevButton.style.display = 'none';
      currentStep.classList.add('active');
    }

    // add next button under whole form
    var nextButton = document.createElement('button');
    nextButton.innerHTML = options.nextButtonLabel;
    nextButton.classList.add('form-next-button');
    if(options.nextButtonClasses) {
      let classes = options.nextButtonClasses.split(' ');
      classes.forEach(function (className) {
        nextButton.classList.add(className);
      });
    }
    nav.appendChild(nextButton);

    // set data-count attribute to fieldset
    for (let step = 0; step < fieldsetCount; step++) {
      fieldsets[step].setAttribute('data-count', step);
    }

    prevButton.addEventListener('click', function() {
      persistData(form);
      // currentFieldset = prevButtonClicked(currentFieldset, form, prevButton, nextButton);
      currentFieldset = jumpToFieldset(currentFieldset, form, prevButton, nextButton, fieldsetCount, 'prev');
      currentStep = updateCurrentStep(progress, currentStep, currentFieldset, element, form);
      document.getElementById(anchorId).scrollIntoView({
        behavior: 'smooth'
      });
    });

    nextButton.addEventListener('click', function() {
      persistData(form);
      // currentFieldset = nextButtonClicked(currentFieldset, form, prevButton, nextButton, fieldsetCount);
      currentFieldset = jumpToFieldset(currentFieldset, form, prevButton, nextButton, fieldsetCount, 'next');
      currentStep = updateCurrentStep(progress, currentStep, currentFieldset, element, form);
      document.getElementById(anchorId).scrollIntoView({
        behavior: 'smooth'
      });
    });

    addDeleteDiv(form, fieldsets);

    // persist data on submit
    form.addEventListener('submit', function(event) {
      persistData(form);
    });

    let submitButton = form.querySelector('button[type="submit"]');
    submitButton.addEventListener('click', function() {
      persistData(form);
      form.querySelectorAll(':invalid').forEach(field => {
        console.log(field);
        field.classList.add('invalid');
      });
      // Focus the first invalid field
      form.querySelector(':invalid').focus();
      let firstField = form.querySelector(':invalid');

      currentFieldset = parseInt(firstField.closest('fieldset').getAttribute('data-count'));
      currentFieldset = jumpToFieldset(currentFieldset, form, prevButton, nextButton, fieldsetCount, currentFieldset);
      currentStep = updateCurrentStep(progress, currentStep, currentFieldset, element, form);
      document.getElementById(anchorId).scrollIntoView({
        behavior: 'smooth'
      });

    });

  });

  function updateCurrentStep(progress, currentStep, currentFieldset, element, form) {
    // console.log(currentFieldset);
    let currentFieldsetElement = element.querySelector('fieldset[data-count="' + currentFieldset + '"]');
    let height = currentFieldsetElement.offsetHeight;
    form.style.height = height + 'px';

    currentStep.classList.remove('active');
    currentStep.classList.add('done');
    currentStep = progress.querySelector('[data-stepcount="' + currentFieldset + '"]');
    currentStep.classList.add('active');
    return currentStep;
  }

  function jumpToFieldset(currentFieldset, form, prevButton, nextButton, fieldsetCount, jumpDirection) {

    switch(jumpDirection) {
      case 'first':
        currentFieldset = 0;
        break;
      case 'prev':
        if(currentFieldset > 0) {
          currentFieldset--;
        }
        break;
      case 'next':
        if(currentFieldset < fieldsetCount - 1) {
          currentFieldset++;
        }
        break;
      case 'last':
        currentFieldset = fieldsetCount - 1;
        break;
      case typeof jumpDirection === 'number':
        if(jumpDirection >= 0 && jumpDirection < fieldsetCount) {
          currentFieldset = jumpDirection;
        }
        break;
    }

    // jump to fieldset
    if(currentFieldset === 0) {
      form.style.left = '0';
      prevButton.style.display = 'none';
      nextButton.style.display = 'inline-block';
    } else if(currentFieldset > 0 && currentFieldset < fieldsetCount - 1) {
      form.style.left = '-' + currentFieldset + '00%';
      prevButton.style.display = 'inline-block';
      nextButton.style.display = 'inline-block';
    } else if(currentFieldset === fieldsetCount - 1) {
      form.style.left = '-' + currentFieldset + '00%';
      prevButton.style.display = 'inline-block';
      nextButton.style.display = 'none';
    }

    repeatedInput(form);
    return currentFieldset;
  }

  function repeatedInput(form) {
    // transfer repeated Input to _again field
    // console.log('repeated input...');
    form.querySelectorAll('input[name$="_again"]').forEach(function (input) {
      let name = input.name;
      let value = form.querySelector('input[name="' + name.replace('_again', '') + '"]').value;
      if(value) {
        input.value = value;
      } else {
        input.value = '';
      }
    });
  }

  function persistData(form) {
    // persist data
    // console.log('persisting data...');
    let data = new FormData(form);
    // console.log(data);
    let formObj = serialize(data);
    // console.log(formObj);
    localStorage.setItem(form.id, JSON.stringify(formObj));
    // console.log(localStorage.getItem(form.id));
  }

  function serialize (data) {
    let obj = {};
    for (let [key, value] of data) {
      if (key.endsWith('_csrf_token')) {
        //console.log('csrf token found - do not store in local storage');
      } else if (obj[key] !== undefined) {
        if (!Array.isArray(obj[key])) {
          obj[key] = [obj[key]];
        }
        obj[key].push(value);
      } else {
        obj[key] = value;
      }
    }
    return obj;
  }

  function findLabelForControl(el) {
    var idVal = el.id;
    let labels = document.getElementsByTagName('label');
    for( var i = 0; i < labels.length; i++ ) {
      if (labels[i].htmlFor == idVal)
        return labels[i];
    }
  }

  function deleteLocalData(formId) {
    localStorage.removeItem(formId);
  }

  function addDeleteDiv(form, fieldsets) {
    let fieldsetCount = fieldsets.length;
    let fieldset = fieldsets[fieldsetCount - 1];
    let div = document.createElement('div');
    if(options.deleteDivClasses) {
      let classes = options.deleteDivClasses.split(' ');
      classes.forEach(function (className) {
        div.classList.add(className);
      });
    }
    div.innerHTML = options.deleteDataHeadline + options.deleteDataText;
    let deleteButton = document.createElement('button');
    deleteButton.innerHTML = options.deleteDataButtonLabel;
    deleteButton.classList.add('delete-data-button');
    if(options.deleteDataButtonClasses) {
      let classes = options.deleteDataButtonClasses.split(' ');
      classes.forEach(function (className) {
        deleteButton.classList.add(className);
      });
    }
    deleteButton.addEventListener('click', function(e) {
      e.preventDefault();
      deleteLocalData(form.id);
      location.reload();
      alert('Ihre Daten wurden gelöscht.');
    });
    div.appendChild(deleteButton);
    fieldset.appendChild(div);
  }

  class CheckboxSwitch {
    constructor(domNode) {
      this.switchNode = domNode;
      this.switchNode.addEventListener('focus', () => this.onFocus(event));
      this.switchNode.addEventListener('blur', () => this.onBlur(event));
    }

    onFocus(event) {
      event.currentTarget.parentNode.classList.add('focus');
    }

    onBlur(event) {
      event.currentTarget.parentNode.classList.remove('focus');
    }
  }

  // Initialize the Switch component on all matching DOM nodes
  Array.from(
    document.querySelectorAll('input[type=checkbox][role^=switch]')
  ).forEach((element) => new CheckboxSwitch(element));
}