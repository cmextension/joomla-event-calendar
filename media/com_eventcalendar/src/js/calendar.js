const moment = require('moment');
let ec;

((Joomla, document) => {
  if (!Joomla) {
    throw new Error('core.js was not properly initialised');
  }

  const handleError = (error) => {
    let message;

    if (typeof error === 'object') {
      const response = JSON.parse(error.response);
      message = response.message;
    } else if (typeof error === 'string') {
      message = error;
    }

    if (!message) {
      return;
    }

    Joomla.renderMessages({
      error: [message]
    }, undefined, false, 10000);

    window.scroll({
      top: 0,
      left: 0,
      behavior: 'smooth'
    });
  };

  // The boot sequence.
  const onBoot = () => {
    ec = new EventCalendar(document.getElementById('ec'), {
      view: 'timeGridWeek',
      events: [],
      eventSources: [
        {
          events: function(fetchInfo, successCallback, failureCallback) {
            const startTime = moment(fetchInfo.start).format('YYYY-MM-DD HH:mm:ss');
            const endTime = moment(fetchInfo.end).format('YYYY-MM-DD HH:mm:ss');

            let url = 'index.php?option=com_eventcalendar&task=ajax.getPublishedEvents&format=json'
            url += '&start_time=' + startTime;
            url += '&end_time=' + endTime;
            url += '&' + Joomla.getOptions('csrf.token', '') + '=1';

            Joomla.request({
              url: url,
              method: 'GET',
              headers: {
                'Content-Type': 'application/json'
              },
              onSuccess: response => {
                try {
                  response = JSON.parse(response);
                } catch (e) {
                  handleError(e.message);

                  return;
                }

                successCallback(response.data);
              },
              onError: xhr => {
                handleError(xhr);
              }
            });
          }
        },
      ],
    });

    // Cleanup.
    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  document.addEventListener('DOMContentLoaded', onBoot);
})(window.Joomla, document);