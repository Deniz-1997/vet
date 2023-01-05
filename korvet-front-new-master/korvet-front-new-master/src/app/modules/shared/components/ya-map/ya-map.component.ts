import {AfterViewInit, Component, Input, OnInit} from '@angular/core';
import {yamaps} from '../../../../utils/ymaps';

@Component({
  selector: 'app-ya-map',
  templateUrl: './ya-map.component.html',
  styleUrls: ['./ya-map.component.css']
})
export class YaMapComponent implements OnInit, AfterViewInit {

  @Input() state = false;

  constructor() {
  }

  ngOnInit() {
  }

  ngAfterViewInit(): void {
    yamaps();
  }

}
