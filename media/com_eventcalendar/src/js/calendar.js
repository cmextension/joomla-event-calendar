const moment = require('moment');
let ec;

((Joomla, document, eventCalendarConfig) => {
  if (!Joomla) {
    throw new Error('core.js was not properly initialised');
  }

  if (typeof eventCalendarConfig === 'undefined') {
    eventCalendarConfig = {
      linkTarget: '_self',
      locale: 'en-GB',
      view: 'timeGridWeek',
      headerToolbar: {
        start: '',
        center: '',
        end: ''
      },
      buttonText: {
        close: 'Close',
        dayGridMonth: 'month',
        listDay: 'list',
        listMonth: 'list',
        listWeek: 'list',
        listYear: 'list',
        resourceTimeGridDay: 'resources',
        resourceTimeGridWeek: 'resources',
        resourceTimelineDay: 'timeline',
        resourceTimelineMonth: 'timeline',
        resourceTimelineWeek: 'timeline',
        timeGridDay: 'day',
        timeGridWeek: 'week',
        today: 'today'
      }
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
      buttonText: eventCalendarConfig.buttonText,
      events: [],
      eventClick: function(info) {
        if (info.event.extendedProps.url) {
          window.open(info.event.extendedProps.url, eventCalendarConfig.linkTarget);
        }
      },
      eventSources: [
        {
          events: function(info, successCallback, failureCallback) {
            const startTime = moment(info.start).format('YYYY-MM-DD HH:mm:ss');
            const endTime = moment(info.end).format('YYYY-MM-DD HH:mm:ss');

            let url = '/index.php?option=com_eventcalendar&task=ajax.getPublishedEvents&format=json'
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
    });

    // Cleanup.
    document.removeEventListener('DOMContentLoaded', onBoot);
  };

  document.addEventListener('DOMContentLoaded', onBoot);
})(window.Joomla, document, eventCalendarConfig);