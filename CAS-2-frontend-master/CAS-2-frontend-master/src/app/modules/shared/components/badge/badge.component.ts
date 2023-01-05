import {Component, ContentChild, Input, OnInit, TemplateRef} from '@angular/core';

@Component({
  selector: 'app-badge',
  templateUrl: './badge.component.html',
  styleUrls: ['./badge.component.css']
})
export class BadgeComponent implements OnInit {
  @Input() count: string | number;
  @ContentChild('content', {static: true}) content: TemplateRef<any>;

  isShow(): boolean {
    return !(this.count !== undefined && this.count !== '' && (this.count !== '0' && this.count !== 0));
  }

  ngOnInit(): void {
  }
}
