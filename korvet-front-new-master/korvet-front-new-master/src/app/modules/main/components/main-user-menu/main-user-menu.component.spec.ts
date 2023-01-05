import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {MainUserMenuComponent} from './main-user-menu.component';

describe('MainUserMenuComponent', () => {
  let component: MainUserMenuComponent;
  let fixture: ComponentFixture<MainUserMenuComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [MainUserMenuComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MainUserMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
