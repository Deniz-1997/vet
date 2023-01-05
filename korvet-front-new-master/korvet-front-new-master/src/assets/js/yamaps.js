ymaps.ready(function () {
  var myMap = new ymaps.Map('form-map', {
      center: [59.22, 39.89],
      zoom: 12,
      controls: []
    }),
    // Создаем экземпляр класса ymaps.control.SearchControl
    mySearchControl = new ymaps.control.SearchControl({
      options: {
        noPlacemark: true
      }
    }),
    // Результаты поиска будем помещать в коллекцию.
    mySearchResults = new ymaps.GeoObjectCollection(null, {
      hintContentLayout: ymaps.templateLayoutFactory.createClass('$[properties.name]')
    });
  myMap.controls.add(mySearchControl);
  myMap.geoObjects.add(mySearchResults);
  // При клике по найденному объекту метка становится красной.
  mySearchResults.events.add('click', function (e) {
    e.get('target').options.set('preset', 'islands#redIcon');
  });
  // Выбранный результат помещаем в коллекцию.
  mySearchControl.events.add('resultselect', function (e) {
    var index = e.get('index');
    mySearchControl.getResult(index).then(function (res) {
      mySearchResults.add(res);
    });
  }).add('submit', function () {
    mySearchResults.removeAll();
  });

  // Вывод подсказки адресса
  var address;
  $(".inp-address").autocomplete({
    source: function (request, response) {
      ymaps.suggest(request.term).then(function (items) {
        address = items;
        response(address);
      });
    },
    minLength: 2,
    select: function (event, ui) {

    }
  });
});
