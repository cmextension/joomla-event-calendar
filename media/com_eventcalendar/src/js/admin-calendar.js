const moment = require('moment');
let ec;

((Joomla, document, eventCalendarConfig) => {
  if (!Joomla) {
    throw new Error('core.js was not properly initialised');
  }

  if (typeof(eventCalendarConfig) == 'undefined') {
    eventCalendarConfig = {
      linkTarget: '_self',
      locale: 'en-GB',
      view: 'timeGridWeek',
      headerToolbar: {
        start: '',
        center: '',
        end: ''
      },
    };
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
    ec = new EventCalendar(document.getElementById('ec'), {
      locale: eventCalendarConfig.locale,
      view: eventCalendarConfig.view,
      headerToolbar: {
        start: eventCalendarConfig.headerToolbar.start,
        center: eventCalendarConfig.headerToolbar.center,
        end: eventCalendarConfig.headerToolbar.end
      },
      events: [],
      eventSources: [
        {
          events: function(info, successCallback, failureCallback) {
            const startTime = moment(info.start).format('YYYY-MM-DD HH:mm:ss');
            const endTime = moment(info.end).format('YYYY-MM-DD HH:mm:ss');

            let url = 'index.php?option=com_eventcalendar&task=ajax.getEvents&format=json'
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

                ec.setOption('resources', response.data.resources);

                successCallback(response.data.events);
              },
              onError: xhr => {
                handleError(xhr);
              }
            });
          }
        },
      ],
      eventDrop: (info) => {
        onEventChange(info.event);
      },
      eventResize: (info) => {
        onEventChange(info.event);
      },
      eventContent: (info) => {
        const config = {
          popupType: 'iframe',
          src: 'index.php?option=com_eventcalendar&view=event&layout=modal&tmpl=component&id=' + info.event.id,
          textHeader: info.event.title
        };

        let startDate = new Date(info.event.start);

        let html = '';

        if (!info.event.allDay) {
          html += '<time class="ec-event-time" datetime="' + startDate.toISOString() + '" data-joomla-dialog="' + htmlspecialchars(JSON.stringify(config)) + '">' + info.timeText + '</time>';
        }

        html += '<h4 class="ec-event-title" data-joomla-dialog="' + htmlspecialchars(JSON.stringify(config)) + '">' + info.event.title + '</h4>';

        return { html: html };
      }
    });

    // Cleanup.
    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  const htmlspecialchars = (str) => {
      return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
  };

  document.addEventListener('DOMContentLoaded', onBoot);

  const msgListener = event => {
    // Avoid cross origins.
    if (event.origin !== window.location.origin) {
      return;
    }

    if (event.data.messageType === 'com_eventcalendar:close-event-modal') {
      Joomla.Modal.getCurrent().close();
      ec = ec.refetchEvents();
    }
  };

  window.addEventListener('message', msgListener);
})(window.Joomla, document, eventCalendarConfig);
