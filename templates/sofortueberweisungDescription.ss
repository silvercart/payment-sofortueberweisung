<% control SilvercartShoppingCart %>
    <% control SilvercartShoppingCartPositions %>
        <% if SilvercartProduct.ProductNumberShop %>[$SilvercartProduct.ProductNumberShop] - <% end_if %>$getTitle x $Quantity<% if Last %><% else %>; <% end_if %>
    <% end_control %>
<% end_control %>
