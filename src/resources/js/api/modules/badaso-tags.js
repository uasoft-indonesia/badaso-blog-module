import resource from "../../../../../../core/src/resources/js/api/resource";
import QueryString from "../../../../../../core/src/resources/js/api/query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX + "/module/post"
  : "/badaso-api/module/post";

export default {
  browse(data = {}) {
    let ep = apiPrefix + "/v1/tag";
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + "/v1/tag/read";
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/tag/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/tag/add", data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/tag/delete", paramData);
  },
  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/tag/delete-multiple", paramData);
  },
};
