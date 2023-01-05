declare var $: any;

export function selectWidth() {
  $('.ui-selectmenu-js').each(function () {
    const _this = $(this);
    _this.css({'width': 'auto', 'position': 'absolute'});
    const width = _this.parent().width();
    _this.css({'width': width, 'position': 'relative'});
  });
  $('select').each(function () {
    const _this = $(this),
      width = _this.parent().width();
    if (width > 30) {
      _this.parent().css('width', width);
      _this.parents('.form-span').css('width', width + 20);
    }
  });
}
