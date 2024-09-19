const moment = require('moment');

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

  // Update event after it is dropped or resized.
  const onEventChange = (event) => {
    const startTime = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
    const endTime = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
    let url = 'index.php?option=com_eventcalendar&task=ajax.updateEventTime&format=json'
    url += '&' + Joomla.getOptions('csrf.token', '') + '=1';

    const postData = new FormData();
    postData.append('id', event.id);
    postData.append('start_time', startTime);
    postData.append('end_time', endTime);

    Joomla.request({
      url: url,
      method: 'POST',
      data: postData,
      perform: true,
      onSuccess: response => {
        try {
          response = JSON.parse(response);
        } catch (e) {
          handleError(e.message);
        }
      },
      onError: xhr => {
        handleError(xhr);
      }
    });
  };

  // The boot sequence.
  const onBoot = () => {
    let ec = new EventCalendar(document.getElementById('ec'), {
        view: 'timeGridWeek',
        events: [],
        datesSet: (info) => {
          const startTime = moment(info.start).format('YYYY-MM-DD HH:mm:ss');
          const endTime = moment(info.end).format('YYYY-MM-DD HH:mm:ss');

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

              ec.setOption('events', response.data);
            },
            onError: xhr => {
              handleError(xhr);
            }
          });
        },
        eventDrop: (info) => {
          onEventChange(info.event);
        },
        eventResize: (info) => {
          onEventChange(info.event);
        }
      });

    // Cleanup.
    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  document.addEventListener('DOMContentLoaded', onBoot);
})(window.Joomla, document);
