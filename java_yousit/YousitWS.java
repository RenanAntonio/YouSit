/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.br;

import com.google.gson.Gson;
import java.sql.SQLException;
import java.util.List;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.UriInfo;
import javax.ws.rs.Produces;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.PUT;
import javax.ws.rs.PathParam;
import javax.ws.rs.core.MediaType;

/**
 * REST Web Service
 *
 * @author Administrador
 */
@Path("YousitWS")

public class YousitWS {

    @Context
    private UriInfo context;

    /**
     * Creates a new instance of GenericResource
     */
    public YousitWS() {
    }

    /**
     * Retrieves representation of an instance of com.br.GenericResource
     * @return an instance of java.lang.String
     */
     @GET
    @Produces("application/json")
    @Path("Reservas/getAllReservas")
    public String getAllReservas() throws SQLException {
         //EXEMPLO GSON RAPAZ 
        List<Reserva> TodasAsReservas = new ReservaDAO().listaReservas();
         Gson g = new Gson();
         
         return g.toJson(TodasAsReservas);
         
    }
    
   @GET
    @Produces("application/json")
    @Path("reserva/autentica/{login}")
    public String autentica(@PathParam("login") String login) throws SQLException
    {
        ReservaDAO dao = new ReservaDAO();
        boolean existe = dao.autentica(login);
        
        return new Gson().toJson(existe);
        
    }

    /**
     * PUT method for updating or creating an instance of GenericResource
     * @param content representation for the resource
     */
    @PUT
    @Consumes(MediaType.APPLICATION_XML)
    public void putXml(String content) {
    }
}
