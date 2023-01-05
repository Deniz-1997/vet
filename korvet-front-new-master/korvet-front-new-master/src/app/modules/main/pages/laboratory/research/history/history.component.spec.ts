import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ProbeSamplingListComponent } from './history.component';


describe('ShopSettingsListComponent', () => {
  let component: ProbeSamplingListComponent;
  let fixture: ComponentFixture<ProbeSamplingListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ProbeSamplingListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ProbeSamplingListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
