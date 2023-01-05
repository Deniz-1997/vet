import {Component, Input, OnInit} from '@angular/core';
import {getIconPathByType} from 'src/app/utils/get-icon-path';

@Component({
  selector: 'app-file-type-icon',
  templateUrl: './icon-file-type.component.html',
  styleUrls: ['./icon-file-type.component.css']
})
export class IconFileTypeComponent implements OnInit {

  @Input() mimeType: string;
  @Input() class = 'down-ico';
  @Input() alt: string;

  getIcon = getIconPathByType;

  constructor() {
  }

  ngOnInit(): void {
  }

}
