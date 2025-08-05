/**
 * 🏗️ TIPOS PRINCIPALES DEL SISTEMA
 *
 * Definiciones de tipos centralizadas siguiendo principios SOLID
 */
// ===== ENUMS =====
export var NotificationType;
(function (NotificationType) {
    NotificationType["SUCCESS"] = "success";
    NotificationType["ERROR"] = "error";
    NotificationType["WARNING"] = "warning";
    NotificationType["INFO"] = "info";
})(NotificationType || (NotificationType = {}));
export var CartActionType;
(function (CartActionType) {
    CartActionType["ADD_ITEM"] = "ADD_ITEM";
    CartActionType["REMOVE_ITEM"] = "REMOVE_ITEM";
    CartActionType["UPDATE_QUANTITY"] = "UPDATE_QUANTITY";
    CartActionType["CLEAR_CART"] = "CLEAR_CART";
})(CartActionType || (CartActionType = {}));
export var LoadingState;
(function (LoadingState) {
    LoadingState["IDLE"] = "idle";
    LoadingState["LOADING"] = "loading";
    LoadingState["SUCCESS"] = "success";
    LoadingState["ERROR"] = "error";
})(LoadingState || (LoadingState = {}));
//# sourceMappingURL=index.js.map