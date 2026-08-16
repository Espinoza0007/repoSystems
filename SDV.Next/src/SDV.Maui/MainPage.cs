namespace SDV.Maui;

public sealed class MainPage : ContentPage
{
    public MainPage()
    {
        Title = "SDV Next";
        Content = new WebView { Source = "wwwroot/index.html", HorizontalOptions = LayoutOptions.Fill, VerticalOptions = LayoutOptions.Fill };
    }
}

