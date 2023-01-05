import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {Router, ActivatedRoute, NavigationEnd} from '@angular/router';
import {filter} from 'rxjs/operators';

@Component({
    selector: 'app-header',
    templateUrl: './header.component.html',
    styles: []
})
export class HeaderComponent implements OnInit {

    public title = '';

    /**
     * @class DetailComponent
     * @constructor
     */
    public constructor(
        private titleService: Title,
        private router: Router,
        private activatedRoute: ActivatedRoute
    ) {
    }

    ngOnInit() {
        this.router.events.pipe(
            filter(event => event instanceof NavigationEnd)
        ).subscribe(() => {
            this.title = this.activatedRoute.children[0].snapshot.data.title;
            this.titleService.setTitle('Корвет - ' + this.title);
        });
    }
}
