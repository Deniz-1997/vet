import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';

@Component({
  selector: 'app-list-img',
  templateUrl: './list-img.component.html'
})
export class ListImgComponent implements OnInit {
  @Output() outDelete = new EventEmitter();
  @Input() type;
  @Input() images;
  @Input() size;
  c = '#';
  g = '22';
  d = 'demo';

  img;

  constructor() {
  }

  ngOnInit() {
  }


  setRemoveFile(item) {
    this.outDelete.emit(item);
  }
}
