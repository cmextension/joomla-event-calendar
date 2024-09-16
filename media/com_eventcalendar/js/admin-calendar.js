((Joomla, document) => {
  if (!Joomla) {
    throw new Error('core.js was not properly initialised');
  }

  // The boot sequence.
  const onBoot = () => {
    let ec = new EventCalendar(document.getElementById('ec'), {
        view: 'timeGridWeek',
        events: [],
        datesSet: function(info) {
          Joomla.request({
            url: 'index.php?option=com_eventcalendar&task=ajax.getPublishedEvents&format=json&' + Joomla.getOptions('csrf.token', '') + '=1',
            method: 'GET',
            headers: {
              'Content-Type': 'application/json'
            },
            onSuccess: response => {
              try {
                response = JSON.parse(response);
              } catch (e) {
                return;
              }

              ec.setOption('events', response.data);
            }
          });
        }
      });

    // Cleanup.
    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  document.addEventListener('DOMContentLoaded', onBoot);
})(window.Joomla, document);
