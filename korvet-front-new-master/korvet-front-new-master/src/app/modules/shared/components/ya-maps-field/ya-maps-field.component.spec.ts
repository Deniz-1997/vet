import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { YaMapsFieldComponent } from './ya-maps-field.component';

describe('YaMapsFieldComponent', () => {
  let component: YaMapsFieldComponent;
  let fixture: ComponentFixture<YaMapsFieldComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ YaMapsFieldComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(YaMapsFieldComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
