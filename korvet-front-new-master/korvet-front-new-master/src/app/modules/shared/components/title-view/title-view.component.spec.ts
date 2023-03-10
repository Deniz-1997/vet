import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { TitleViewComponent } from './title-view.component';

describe('TitleViewComponent', () => {
  let component: TitleViewComponent;
  let fixture: ComponentFixture<TitleViewComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ TitleViewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TitleViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
