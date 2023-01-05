import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';

@Component({
  selector: 'app-empty-view',
  templateUrl: './empty-view.component.html',
  styleUrls: ['./empty-view.component.css']
})
export class EmptyViewComponent implements OnInit {

  @Input() elementName: string;
  @Input() title: string;
  @Input() subtitle: string;
  @Input() search: string;
  @Input() addLink: string | Array<string>;
  @Input() addLinInvisible = false;
  @Input() buttons: Array<{ title: string, action: string }>;
  @Output() buttonClick = new EventEmitter<{ action: string }>();

  constructor() {
  }

  ngOnInit(): void {}

  isValidLink(): boolean {
    if (!this.addLink) {
      return false;
    }
    if (Array.isArray(this.addLink)) {
      for (const i in this.addLink) {
        if (this.addLink[i] === '' || this.addLink[i] === null) {
          return false;
        }
      }
    }
    return true;
  }

}
