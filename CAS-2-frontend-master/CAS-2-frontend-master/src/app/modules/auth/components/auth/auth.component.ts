import {HttpClient, HttpParams} from '@angular/common/http';
import {Component, OnDestroy, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {ActivatedRoute, Router} from '@angular/router';
import {Urls} from 'src/app/common/urls';
import {SettingsService} from '../../../../services/settings.service';

@Component({templateUrl: './auth.component.html', styleUrls: ['../auth.scss']})
export class AuthComponent implements OnInit, OnDestroy {
  config: any;
  configHref = '';
  configMailto = '';
  esiaLoading = false;

  constructor(private title: Title,
              private setting: SettingsService,
              private http: HttpClient,
              private route: ActivatedRoute,
              private router: Router
  ) {
    document.body.classList.add('min-width-auto');
    route.queryParams.subscribe(res => {
      if (res['code'] && res['state']) {
        this.esiaLoading = true;
        this.http.get(Urls.apiEsiaUrl + 'data/', {
          params: new HttpParams().set('code', res['code']).set('state', res['state'])
        }).subscribe(httpRes => {
          this.esiaLoading = false;
          if (httpRes['status'] && httpRes['response']) {
            localStorage.setItem('token', JSON.stringify(httpRes['response']));
            router.navigate(['/']);
          }
        }, _ => this.esiaLoading = false);
      }
    });
  }

  ngOnInit(): void {
    this.title.setTitle('КАС Ветеринария');
    this.setting.contactValue.subscribe((values) => {
      this.config = values;
      this.configHref = `tel:${this.config.tell}`;
      this.configMailto = `mailto:${this.config.email}`;
    }
    );
  }

  ngOnDestroy(): void {
    document.body.classList.remove('min-width-auto');
  }

  getEsiaUrl(): void {
    this.esiaLoading = true;
    this.http.get(Urls.apiEsiaUrl).subscribe(result => {
      this.esiaLoading = false;
      if (result['status'] === true && result['response']['url']) {
        window.location.href = result['response']['url'];
      }
    }, _ => this.esiaLoading = false);
  }
}
