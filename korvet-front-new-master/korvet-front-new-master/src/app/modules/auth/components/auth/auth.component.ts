import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {SettingsService} from '../../../../services/settings.service';

@Component({templateUrl: './auth.component.html'})
export class AuthComponent implements OnInit {
  config: any;
  configHref = '';
  configMailto = '';

  constructor(private title: Title,
              private setting: SettingsService
  ) {
    document.body.classList.add('min-width-auto');
  }

  ngOnInit() {
    this.title.setTitle('КОРВЕТ');
    this.config = this.setting.contactValue.value;
    this.setting.contactValue.subscribe(() => {
        this.configHref = `tel:${this.config.tell}`;
        this.configMailto = `mailto:${this.config.email}`;
      }
    );
  }

  ngOnDestroy() {
    document.body.classList.remove('min-width-auto');
  }
}
